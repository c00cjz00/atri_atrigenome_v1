#!/bin/bash
# Eample: 03-FASTQ_and_GC_report.sh /tmp/job_50/SRR12362016_R1.fastq.gz /tmp/job_50/SRR12362016_R2.fastq.gz tmp/ 1 1

# Configure
#Scripts="/var/www/html/github/phplogin/modules/Scripts"
sleep_time=10

# Input
fastq_R1=$1
fastq_R2=$2
workspace=$3
job_id=$4
expNum=$5
Scripts=$6
author=$7
api_dir=$8

docker_name=docker_${job_id}
# filter
basename=$(basename ${fastq_R1})
SRA_ID="$(cut -d'_' -f1 <<<${basename})"
#mkdir -p ${workspace}

# 0. Copy fastq
echo "0. Copy fastq"
mkdir -p ${workspace}
cp ${fastq_R1} ${workspace}/${SRA_ID}_R1.fastq.gz
cp ${fastq_R2} ${workspace}/${SRA_ID}_R2.fastq.gz

# 1. fastqc
echo "1. fastqc"
sleep ${sleep_time}
#https://hub.docker.com/r/staphb/fastqc
#IMAGE=biocontainers/fastqc:v0.11.9_cv8
IMAGE=staphb/fastqc:0.12.1
docker run \
--user $(id -u) \
--name ${docker_name} \
-v ${workspace}:/workspace \
--rm ${IMAGE} \
bash -c "cd /workspace; fastqc ${SRA_ID}_R1.fastq.gz -o ./ && fastqc ${SRA_ID}_R2.fastq.gz -o ./ && unzip ${SRA_ID}_R1_fastqc.zip && unzip ${SRA_ID}_R2_fastqc.zip"

# 2. fastq_quality_trimmer
echo "2. fastq_quality_trimmer"
docker wait ${docker_name}
sleep ${sleep_time}
IMAGE=biocontainers/fastxtools:v0.0.14_cv2 
docker run \
--user $(id -u) \
--name ${docker_name} \
-v ${workspace}:/workspace \
--rm ${IMAGE} \
bash -c "cd /workspace; gunzip -c ${SRA_ID}_R1.fastq.gz |fastq_quality_trimmer -t 30 -o ${SRA_ID}_R1.fastq.trim; gunzip -c ${SRA_ID}_R2.fastq.gz |fastq_quality_trimmer -t 30 -o ${SRA_ID}_R2.fastq.trim"

# 3. filter
echo "3. filter"
docker wait ${docker_name}
sleep ${sleep_time}
awk 'BEGIN {OFS = "\n"} {header = $0 ; getline seq ; getline qheader ; getline qseq ; if (length(seq) > 30) {print header, seq, qheader, qseq}}' < ${workspace}/${SRA_ID}_R1.fastq.trim > ${workspace}/${SRA_ID}_R1.fastq.filtrate
awk 'BEGIN {OFS = "\n"} {header = $0 ; getline seq ; getline qheader ; getline qseq ; if (length(seq) > 30) {print header, seq, qheader, qseq}}' < ${workspace}/${SRA_ID}_R2.fastq.trim > ${workspace}/${SRA_ID}_R2.fastq.filtrate

# 4. fastqCombinePairedEnd, import gzip, import sys
echo "4. fastqCombinePairedEnd"
docker wait ${docker_name}
sleep ${sleep_time}
IMAGE=python:3.8.18
docker run \
--user $(id -u) \
--name ${docker_name} \
-v ${workspace}:/workspace \
-v ${Scripts}:/Scripts \
--rm ${IMAGE} \
bash -c "cd /workspace; python3 /Scripts/fastqCombinePairedEnd.py ${SRA_ID}_R1.fastq.filtrate ${SRA_ID}_R2.fastq.filtrate"
#python3 /var/www/html/github/phplogin/app/Scripts/fastqCombinePairedEnd.py ${workspace}/${SRA_ID}_R1.fastq.filtrate ${workspace}/${SRA_ID}_R2.fastq.filtrate

# 5. count depth
echo "5. count depth"
docker wait ${docker_name}
sleep ${sleep_time}
cat ${workspace}/${SRA_ID}_R1.fastq.filtrate_pairs_R1.fastq | paste - - - - | cut -f 2 | tr -d '\n' | wc -c > ${workspace}/${SRA_ID}_R1.fastq.filtrate_pairs_R1.BaseCount 
cat ${workspace}/${SRA_ID}_R2.fastq.filtrate_pairs_R2.fastq | paste - - - - | cut -f 2 | tr -d '\n' | wc -c > ${workspace}/${SRA_ID}_R2.fastq.filtrate_pairs_R2.BaseCount 
grep "" ${workspace}/${SRA_ID}_*BaseCount >  ${workspace}/${SRA_ID}.depth

# 6. GC report
echo "6. GC report"
docker wait ${docker_name}
sleep ${sleep_time}
IMAGE=php:7.4.33-cli
docker run \
--user $(id -u) \
--name ${docker_name} \
-v ${workspace}:/workspace \
-v ${Scripts}:/Scripts \
--rm ${IMAGE} \
bash -c "cd /workspace; php /Scripts/fastqc_gc.php /workspace/${SRA_ID}_R1.fastq.gz /workspace/${SRA_ID}_R2.fastq.gz"
#php /var/www/html/github/phplogin/app/Scripts/fastqc_gc.php ${workspace}/${SRA_ID}_R1.fastq.gz ${workspace}/${SRA_ID}_R2.fastq.gz

#7. Print report
php /var/www/html/github/phplogin/modules/app/04-FASTQ_and_GC_report_printer.php ${expNum} ${workspace}/${SRA_ID}.record > ${workspace}/${SRA_ID}.record.html
sleep 3
wkhtmltopdf ${workspace}/${SRA_ID}.record.html ${workspace}/${SRA_ID}.record.pdf

#8. Clean file
echo "8. Clean file"
sleep 3
rm -f ${workspace}/${SRA_ID}*trim ${workspace}/${SRA_ID}*filtrate*
rm -f ${workspace}/${SRA_ID}_R1.fastq.gz ${workspace}/${SRA_ID}_R2.fastq.gz
rm -rf ${workspace}/${SRA_ID}_R1_fastqc ${workspace}/${SRA_ID}_R2_fastqc

#9. Check is finish
FILE=${workspace}/${SRA_ID}.record
#if [ -f "$FILE" ]; then
# echo "$FILE exists."
# php /var/www/html/github/phplogin/api.php ${job_id} 1
#else
# echo "$FILE error."
# php /var/www/html/github/phplogin/api.php ${job_id} 2
#fi
 
# Check if the file is empty
if [ ! -s "${FILE}" ]; then
 echo "$FILE error."
 php ${api_dir}/api.php ${job_id} 2
else
 echo "$FILE exists."
 php ${api_dir}/api.php ${job_id} 1
fi
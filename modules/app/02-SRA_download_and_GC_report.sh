#!/bin/bash
# Eample: 02-SRA_download_and_GC_report.sh SRR12362016 /tmp/123 10

# Configure
ascp_dir="/home/atri/.aspera"
Scripts="/var/www/html/github/phplogin/modules/Scripts"
sleep_time=10

# Input
SRA_ID=$1
workspace=$2
job_id=$3

# 0. Download sra
echo "0. Download SRA"
x=$(echo ${SRA_ID} | cut -b1-6)
y=`echo ${SRA_ID: -2}`
fastq01="vol1/fastq/${x}/0${y}/${SRA_ID}/${SRA_ID}_1.fastq.gz" 
fastq02="vol1/fastq/${x}/0${y}/${SRA_ID}/${SRA_ID}_2.fastq.gz" 
mkdir -p ${workspace}
${ascp_dir}/connect/bin/ascp -P33001 -v -QT -l 300m  -k1 -i ${ascp_dir}/connect/etc/asperaweb_id_dsa.openssh era-fasp@fasp.sra.ebi.ac.uk:${fastq01} ${workspace}/
${ascp_dir}/connect/bin/ascp -P33001 -v -QT -l 300m  -k1 -i ${ascp_dir}/connect/etc/asperaweb_id_dsa.openssh era-fasp@fasp.sra.ebi.ac.uk:${fastq02} ${workspace}/
mv ${workspace}/${SRA_ID}_1.fastq.gz ${workspace}/${SRA_ID}_R1.fastq.gz 
mv ${workspace}/${SRA_ID}_2.fastq.gz ${workspace}/${SRA_ID}_R2.fastq.gz 

# 1. fastqc
echo "1. fastqc"
sleep ${sleep_time}
IMAGE=biocontainers/fastqc:v0.11.9_cv8
docker run \
--name ${job_id} \
-v ${workspace}:/workspace \
--rm ${IMAGE} \
bash -c "cd /workspace; fastqc ${SRA_ID}_R1.fastq.gz -o ./ && fastqc ${SRA_ID}_R2.fastq.gz -o ./ && unzip ${SRA_ID}_R1_fastqc.zip && unzip ${SRA_ID}_R2_fastqc.zip"

# 2. fastq_quality_trimmer
echo "2. fastq_quality_trimmer"
docker wait ${job_id}
sleep ${sleep_time}
IMAGE=biocontainers/fastxtools:v0.0.14_cv2 
docker run \
--name ${job_id} \
-v ${workspace}:/workspace \
--rm ${IMAGE} \
bash -c "cd /workspace; gunzip -c ${SRA_ID}_R1.fastq.gz |fastq_quality_trimmer -t 30 -o ${SRA_ID}_R1.fastq.trim; gunzip -c ${SRA_ID}_R2.fastq.gz |fastq_quality_trimmer -t 30 -o ${SRA_ID}_R2.fastq.trim"

# 3. filter
echo "3. filter"
docker wait ${job_id}
sleep ${sleep_time}
awk 'BEGIN {OFS = "\n"} {header = $0 ; getline seq ; getline qheader ; getline qseq ; if (length(seq) > 30) {print header, seq, qheader, qseq}}' < ${workspace}/${SRA_ID}_R1.fastq.trim > ${workspace}/${SRA_ID}_R1.fastq.filtrate
awk 'BEGIN {OFS = "\n"} {header = $0 ; getline seq ; getline qheader ; getline qseq ; if (length(seq) > 30) {print header, seq, qheader, qseq}}' < ${workspace}/${SRA_ID}_R2.fastq.trim > ${workspace}/${SRA_ID}_R2.fastq.filtrate

# 4. fastqCombinePairedEnd, import gzip, import sys
echo "4. fastqCombinePairedEnd"
docker wait ${job_id}
sleep ${sleep_time}
IMAGE=python:3.8.18
docker run \
--name ${job_id} \
-v ${workspace}:/workspace \
-v ${Scripts}:/Scripts \
--rm ${IMAGE} \
bash -c "cd /workspace; python3 /Scripts/fastqCombinePairedEnd.py ${SRA_ID}_R1.fastq.filtrate ${SRA_ID}_R2.fastq.filtrate"
#python3 /var/www/html/github/phplogin/app/Scripts/fastqCombinePairedEnd.py ${workspace}/${SRA_ID}_R1.fastq.filtrate ${workspace}/${SRA_ID}_R2.fastq.filtrate

# 5. count depth
echo "5. count depth"
docker wait ${job_id}
sleep ${sleep_time}
cat ${workspace}/${SRA_ID}_R1.fastq.filtrate_pairs_R1.fastq | paste - - - - | cut -f 2 | tr -d '\n' | wc -c > ${workspace}/${SRA_ID}_R1.fastq.filtrate_pairs_R1.BaseCount 
cat ${workspace}/${SRA_ID}_R2.fastq.filtrate_pairs_R2.fastq | paste - - - - | cut -f 2 | tr -d '\n' | wc -c > ${workspace}/${SRA_ID}_R2.fastq.filtrate_pairs_R2.BaseCount 
grep "" ${workspace}/${SRA_ID}_*BaseCount >  ${workspace}/${SRA_ID}.depth

# 6. GC report
echo "6. GC report"
docker wait ${job_id}
sleep ${sleep_time}
IMAGE=php:7.4.33-cli
docker run \
--name ${job_id} \
-v ${workspace}:/workspace \
-v ${Scripts}:/Scripts \
--rm ${IMAGE} \
bash -c "cd /workspace; php /Scripts/fastqc_gc.php /workspace/${SRA_ID}_R1.fastq.gz /workspace/${SRA_ID}_R2.fastq.gz"
#php /var/www/html/github/phplogin/app/Scripts/fastqc_gc.php ${workspace}/${SRA_ID}_R1.fastq.gz ${workspace}/${SRA_ID}_R2.fastq.gz

#7. Clean file
echo "7. Clean file"
rm -f ${workspace}/${SRA_ID}*trim ${workspace}/${SRA_ID}*filtrate*
rm -rf ${workspace}/${SRA_ID}_R1_fastqc ${workspace}/${SRA_ID}_R2_fastqc

 
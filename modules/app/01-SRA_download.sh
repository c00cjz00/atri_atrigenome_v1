#!/bin/bash
# Eample: 01-SRA_download.sh SRR12362016 /tmp/123 3

# Configure

# Input
SRA_ID=$1
workspace=$2
job_id=$3
author=$4
api_dir=$5
ascp_dir="/home/${author}/.aspera"


# 0. Download sra
echo "0. Download sra"
x=$(echo ${SRA_ID} | cut -b1-6)
y=`echo ${SRA_ID: -2}`
fastq01="vol1/fastq/${x}/0${y}/${SRA_ID}/${SRA_ID}_1.fastq.gz" 
fastq02="vol1/fastq/${x}/0${y}/${SRA_ID}/${SRA_ID}_2.fastq.gz" 
#mkdir -p ${workspace}
${ascp_dir}/connect/bin/ascp -P33001 -v -QT -l 300m  -k1 -i ${ascp_dir}/connect/etc/asperaweb_id_dsa.openssh era-fasp@fasp.sra.ebi.ac.uk:${fastq01} ${workspace}/
${ascp_dir}/connect/bin/ascp -P33001 -v -QT -l 300m  -k1 -i ${ascp_dir}/connect/etc/asperaweb_id_dsa.openssh era-fasp@fasp.sra.ebi.ac.uk:${fastq02} ${workspace}/
FILE=${workspace}/${SRA_ID}_2.fastq.gz
if [ -f "$FILE" ]; then
 echo "$FILE exists."
 mv ${workspace}/${SRA_ID}_1.fastq.gz ${workspace}/${SRA_ID}_R1.fastq.gz 
 mv ${workspace}/${SRA_ID}_2.fastq.gz ${workspace}/${SRA_ID}_R2.fastq.gz 
 php ${api_dir}/api.php ${job_id} 1
else
 echo "$FILE error."
 php ${api_dir}/api.php ${job_id} 2
fi
 
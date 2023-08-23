#!/bin/bash

if [ $# -ne 2 ]; then
  echo "Usage: $0 <recipient_email> <recipient_name>"
  exit 1
fi

recipient_email=$1
recipient_name=$2

email_file=$(tempfile)

echo "From: CHS CENTENNIAL RUN 100th <38332@chs.ac.th>" >> $email_file
echo "To: $recipient_name <$recipient_email>" >> $email_file
echo "Subject: ยืนยันการชำระเงินของคุณ (Your Payment Confirmed)" >> $email_file
echo "Content-Type: text/plain; charset=\"utf8\"" >> $email_file
echo >> $email_file
echo "สวัสดีคุณ $recipient_name," >> $email_file
echo >> $email_file
echo "ขอบคุณที่ท่านได้ทำการชำระเงินสำหรับงาน CHS Centennial Run 100th ครับ/ค่ะ" >> $email_file
echo "ทางเราได้รับการชำระเงินเรียบร้อยแล้ว" >> $email_file
echo "กรุณาตรวจสอบรายละเอียดด้านล่าง:" >> $email_file
echo "https://running.chs.ac.th/payment" >> $email_file
echo >> $email_file

echo "หากท่านมีคำถามหรือต้องการแก้ไขรายละเอียดการชำระเงิน โปรดติดต่อเรา" >> $email_file
echo >> $email_file
echo "ขอแสดงความนับถือ," >> $email_file
echo "ทีมงาน CHS Centennial Run 100th" >> $email_file
# Send the email
curl \
	--url 'smtps://smtp.gmail.com:465' \
	--ssl-reqd \
	--mail-from '38332@chs.ac.th' \
	--mail-rcpt $recipient_email \
	--upload-file $email_file \
	--user '38332@chs.ac.th:zhtezcrpoqpemktk'

# Cleanup
rm -f $email_file
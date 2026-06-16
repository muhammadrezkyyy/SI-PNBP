import fitz
import sys

pdf_path = "D:/laragon/www/SI-PNBP/storage/app/private/simponi-pdfs/Nz2nnl67YDFA5ouWNShh9U7eh0B13PrJF9XLPbs8.pdf"
doc = fitz.open(pdf_path)
page = doc[0]
image_list = page.get_images(full=True)

for img_index, img in enumerate(image_list):
    xref = img[0]
    base_image = doc.extract_image(xref)
    image_bytes = base_image["image"]
    image_ext = base_image["ext"]
    
    with open(f"D:/laragon/www/SI-PNBP/public/images/kemenkeu_logo.{image_ext}", "wb") as f:
        f.write(image_bytes)
    print(f"Extracted image to kemenkeu_logo.{image_ext}")
    break # Just get the first image which is usually the logo

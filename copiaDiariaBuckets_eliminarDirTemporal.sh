#!/bin/bash
# Se realiza una copia diaria de toda la información de cada uno de los buckets. El bucket de copia es nombreBucket-copia
aws s3 sync s3://bucketpruebaseven s3://bucketpruebaseven-copia --storage-class GLACIER_IR
# Elimina el directorio temporal de descarga de documentos del proyecto
rm -rf storage/app/public/temp/*

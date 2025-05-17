@echo off
"C:\xampp\mysql\bin\mysql" -u root -e "CREATE DATABASE IF NOT EXISTS dineease;"
"C:\xampp\mysql\bin\mysql" -u root dineease < "c:\Users\Emad\Downloads\dbs\dbs.sql"
echo Database imported successfully. 
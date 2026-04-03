import hashlib
import os

stored_username = "admin"

salt = os.urandom(16)

stored_hash = hashlib.sha256(input("Enter your password: ").encode() + salt).hexdigest()
#stored_hash2 = hashlib.sha256(input("Enter your password : ").encode()).hexdigest()

print("stored hash using salt : ",stored_hash) 
#print("stored hash without salt : ",stored_hash2) 


username = input("Enter username: ")
password = input("Enter password: ")

login_hash = hashlib.sha256(password.encode() + salt).hexdigest()

if username == stored_username and login_hash == stored_hash:
    print("Login successful")
else:
    print("Login unsuccessful")
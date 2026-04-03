import hashlib

stored_username = "admin"
stored_hash = hashlib.sha256(input("Enter your password : ").encode()).hexdigest()

username = input("Enter username: ")

login_hash = hashlib.sha256(input("Enter your password: ").encode()).hexdigest()
# print(login_hash)

if username == stored_username and login_hash == stored_hash:
    print("Login successful")
else:
    print("Login unsuccessful")
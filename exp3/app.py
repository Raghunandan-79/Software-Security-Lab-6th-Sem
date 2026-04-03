from flask import Flask, render_template, request, redirect, url_for
import hashlib
import os

app = Flask(__name__)

stored_username = "admin"

# Generate salt once
salt = os.urandom(16)

# Stored password simulation
stored_password = "raghu123"

stored_hash = hashlib.sha256(
    stored_password.encode() + salt
).hexdigest()


@app.route("/", methods=["GET", "POST"])
def login():

    if request.method == "POST":

        username = request.form["username"]
        password = request.form["password"]

        login_hash = hashlib.sha256(
            password.encode() + salt
        ).hexdigest()

        if username == stored_username and login_hash == stored_hash:
            return redirect(url_for("dashboard"))

        else:
            return render_template(
                "login.html",
                message="Login Failed ❌"
            )

    return render_template("login.html", message="")


@app.route("/dashboard")
def dashboard():
    return render_template("dashboard.html")


if __name__ == "__main__":
    app.run(debug=True)
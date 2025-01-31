from flask import Flask, render_template, jsonify, session, redirect, url_for, request
from functools import wraps
import json

app = Flask(__name__)
app.secret_key = 'your-secret-key-here'  # In production, use a proper secret key

# Simulated database of users and their data
USERS = {
    '1': {'name': 'Alice', 'email': 'alice@example.com', 'role': 'admin', 'private_data': 'sensitive_info_1'},
    '2': {'name': 'Bob', 'email': 'bob@example.com', 'role': 'user', 'private_data': 'sensitive_info_2'},
    '3': {'name': 'Charlie', 'email': 'charlie@example.com', 'role': 'user', 'private_data': 'sensitive_info_3'}
}

def login_required(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if 'user_id' not in session:
            return render_template('login.html', error="Please login first")
        return f(*args, **kwargs)
    return decorated_function

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        user_id = request.form.get('user_id')
        if user_id in USERS:
            session['user_id'] = user_id
            # Using hardcoded routes instead of url_for with parameters
            return redirect('/profile/' + user_id)
    return render_template('login.html')

@app.route('/profile/<user_id>')
@login_required
def profile(user_id):
    # IDOR Vulnerability: No authorization check if the logged-in user
    # has permission to view this profile
    if user_id in USERS:
        return render_template('profile.html', user=USERS[user_id])
    return "Profile not found", 404

@app.route('/api/user/<user_id>/data')
@login_required
def get_user_data(user_id):
    # Another IDOR vulnerability in the API endpoint
    if user_id in USERS:
        return jsonify(USERS[user_id])
    return jsonify({"error": "User not found"}), 404

if __name__ == '__main__':
    app.run(debug=True, port=5000) 
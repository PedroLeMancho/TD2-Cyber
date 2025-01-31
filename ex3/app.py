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
            return redirect(url_for('login'))
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
            return redirect(url_for('profile', user_id=user_id))
    return render_template('login.html')

@app.route('/profile/<user_id>')
@login_required
def profile(user_id):
    # IDOR Fix: Check if the logged-in user has permission to view this profile
    if session['user_id'] == user_id or USERS.get(user_id, {}).get('role') == 'admin':
        if user_id in USERS:
            return render_template('profile.html', user=USERS[user_id])
        return "Profile not found", 404
    return "You do not have permission to view this profile", 403

@app.route('/api/user/<user_id>/data')
@login_required
def get_user_data(user_id):
    # IDOR Fix: Check if the logged-in user has permission to access this user's data
    if session['user_id'] == user_id or USERS.get(user_id, {}).get('role') == 'admin':
        if user_id in USERS:
            return jsonify(USERS[user_id])
        return jsonify({"error": "User not found"}), 404
    return jsonify({"error": "Unauthorized access"}), 403

if __name__ == '__main__':
    app.run(debug=True, port=5000)

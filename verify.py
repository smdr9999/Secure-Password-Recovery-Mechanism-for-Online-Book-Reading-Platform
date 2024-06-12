import sys
import os
import mysql.connector
from config import db_config  # Import database configuration
import importlib.util
import hashlib

def calculate_file_hash(file_path):
    # Initialize the hash object
    hash_obj = hashlib.sha256()

    # Read the file in binary mode and update the hash object
    with open(file_path, 'rb') as file:
        while True:
            # Read a chunk of data
            chunk = file.read(4096)
            if not chunk:
                break
            # Update the hash object with the chunk
            hash_obj.update(chunk)

    # Get the hexadecimal representation of the hash digest
    file_hash = hash_obj.hexdigest()
    return file_hash


# Function to retrieve password hash from the database
def get_password_hash(email):
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()
    cursor.execute('SELECT password_hash FROM users WHERE email = %s', (email,))
    result = cursor.fetchone()
    cursor.close()

    if result:
        return result[0]
    else:
        return None

def find_original_text(target_hash):
    file_path = 'hashTheWordsSorted.txt'

    with open(file_path, 'r', encoding='utf-8') as infile:
        lines = infile.readlines()
        hash_lines = [(line.split(':')[0].strip(), ':'.join(line.split(':')[1:]).strip()) for line in lines]
        #hash_lines.sort(key=lambda x: x[0])  # Sort by hash values

        low = 0
        high = len(hash_lines) - 1
        found = False

        while low <= high and not found:
            mid = (low + high) // 2
            mid_hash, text = hash_lines[mid]
            if mid_hash == target_hash:
                found = True
                return text
            elif mid_hash < target_hash:
                low = mid + 1
            else:
                high = mid - 1

    return None


if __name__ == '__main__':
    file_path = 'hashTheWordsSorted.txt'
    file_hash = calculate_file_hash(file_path)
    #print(f'Hash value of {file_path}: {file_hash}')
    if file_hash=='ffebfb8d53f1036e13ec0dcb6866e2f06bfaf9161a530100f8411fcaff729748':
        # Get email from command-line argument
        email = sys.argv[1].strip()
        # Get password hash from the database
        password_hash = get_password_hash(email)
        
        if password_hash:
            # Perform binary search for hash value in the text file
            password = find_original_text(password_hash)
            if password:
                print(password)  # Output the password
            else:
                print('Password not found.')
        else:
            print('Email not found or password hash not available.')
    else:
        print('Hash Table is altered...Warning!')

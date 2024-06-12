import os
import hashlib

# Function to hash a line using MD5
def hash_line(line):
    return hashlib.md5(line.encode()).hexdigest()

# Path to the directory containing text files
directory_path = r"C:\Users\TOM & JERRY\Downloads\Commonpasswords"

# Output file to store hashed lines
output_file = 'hashTheWords.txt'
count=0
# Loop through each file in the directory
with open(output_file, 'w', encoding='utf-8') as outfile:
    for filename in os.listdir(directory_path):
        if filename.endswith('.txt'):  # Process only text files
            count+=1
            print(f"{count}. Hashing lines in file: {filename}")  # Print the file name
            file_path = os.path.join(directory_path, filename)
            with open(file_path, 'r', encoding='utf-8') as infile:
                for line in infile:
                    hashed_line = hash_line(line.strip())
                    outfile.write(f'{hashed_line}: {line}')

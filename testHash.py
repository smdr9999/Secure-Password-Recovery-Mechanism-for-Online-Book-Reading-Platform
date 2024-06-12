import os
import sys

def binary_search_in_file(file_name, target_hash):
    result_text = None
    with open(file_name, 'r', encoding='utf-8') as infile:
        lines = infile.readlines()
        hash_lines = [(line.split(':')[0].strip(), ':'.join(line.split(':')[1:]).strip()) for line in lines]

        low = 0
        high = len(hash_lines) - 1
        found = False

        while low <= high and not found:
            mid = (low + high) // 2
            mid_hash, text = hash_lines[mid]
            if mid_hash == target_hash:
                found = True
                result_text = text
            elif mid_hash < target_hash:
                low = mid + 1
            else:
                high = mid - 1

    return result_text

file_name = "hashTheWordsSorted.txt"
target_hash = sys.argv[1]

# Perform binary search for the hash in the file
result_text = binary_search_in_file(file_name, target_hash)

if result_text is not None:
    print(f"Text for hash {target_hash}: {result_text}")
else:
    print(f"Hash {target_hash} not found in the file.")

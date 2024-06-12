# Read the content of hashTheWords.txt and sort lines based on hash value
with open('hashTheWords.txt', 'r', encoding='utf-8') as infile:
    lines = infile.readlines()
    # Sort lines based on hash value
    sorted_lines = sorted(lines, key=lambda x: x.split(':')[0])

# Remove duplicates based on hash value
unique_lines = []
seen_hashes = set()
for line in sorted_lines:
    hash_value = line.split(':')[0]
    if hash_value not in seen_hashes:
        unique_lines.append(line)
        seen_hashes.add(hash_value)

# Write the sorted and unique lines back to hashTheWords.txt
with open('hashTheWordsSorted.txt', 'w', encoding='utf-8') as outfile:
    outfile.writelines(unique_lines)

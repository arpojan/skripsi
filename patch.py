import re
with open(r'e:\TUGAS\SKRIPSI\project\index.html', 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(r'href="manifest\.json"', r'href="{{ asset(\'manifest.json\') }}"', content)
content = re.sub(r'href="style\.css"', r'href="{{ asset(\'style.css\') }}"', content)
content = re.sub(r'src="app\.js"', r'src="{{ asset(\'app.js\') }}"', content)
content = re.sub(r'\'\./service-worker\.js\'', r"'{{ asset(\"service-worker.js\") }}'", content)

with open(r'c:\xampp\htdocs\skripsi\sparring\resources\views\welcome.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

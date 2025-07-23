ra = {"liz": 229874, "Hugo": 215781, "Sofia": 199745}
print(ra.items())
print(ra.keys())
print(ra.values())

# outro método

print(ra.get("Hugo"))
print(ra.get("Maria"))
print(ra.get("Maria","N/A"))

# outro método

for nome,  numero in ra.items():
    print(nome, numero, sep=' ')
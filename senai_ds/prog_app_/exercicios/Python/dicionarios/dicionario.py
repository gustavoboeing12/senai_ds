ra = {"liz": 229874, "Hugo": 215781, "Sofia": 199745}
print(type(ra))

print(ra["liz"])
print(ra["Sofia"])

ra['Hugo'] = 215871
ra['Diego'] = 246754

print(ra)


for x in ra:
    print(x)
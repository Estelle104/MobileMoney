# Reinit
rm writable/database/app.db
touch writable/database/app.db

# Raffraichir
php spark migrate:refresh

# Migration:
php spark migrate

php spark db:seed DatabaseSeeder

# Lancement:
php spark serve



# Entrer dans la base via terminal:
sqlite3 writable/database/app.db
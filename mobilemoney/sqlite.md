# Créer les migrations

php spark make:migration CreateClients
php spark make:migration CreateProduits
php spark make:migration CreateCaisses
php spark make:migration CreateAchats
php spark make:migration CreateAchatProduits


# Exemple
``` php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 25
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 15
            ],
            'mdp' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}

```

# Creer seeders

php spark make:seeder UserSeeder
<!-- php spark make:seeder ProduitSeeder
php spark make:seeder CaisseSeeder -->

# Exmpel

``` php
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    { 
        $data = [
            [
                'nom' => 'Jean',
                'email' => 'jean@gmail.com',
                'telephone' => '0340000000',
                'mdp' => password_hash('1234', PASSWORD_DEFAULT)
            ],
            [
                'nom' => 'Marie',
                'email' => 'marie@gmail.com',
                'telephone' => '0320000000',
                'mdp' => password_hash('1234', PASSWORD_DEFAULT)
            ]
        ];

        $this->db->table('clients')->insertBatch($data);
    }
}

```

# Creer les tables
php spark migrate

# INserer donnee
php spark db:seed UserSeeder
<!-- php spark db:seed ProduitSeeder
php spark db:seed CaisseSeeder -->


# Reinitialiser 
php spark migrate:rollback
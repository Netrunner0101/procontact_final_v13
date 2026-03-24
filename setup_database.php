<?php
echo "Setting up PostgreSQL database...\n";

try {
    // First, connect to PostgreSQL server (without specifying a database)
    $pdo = new PDO('pgsql:host=tfe;port=5432', 'root', 'root');
    echo "✅ Connected to PostgreSQL server successfully!\n";
    
    // Check if database exists
    $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
    $stmt->execute(['agenda_app']);
    
    if ($stmt->fetchColumn()) {
        echo "✅ Database 'agenda_app' already exists.\n";
    } else {
        echo "Creating database 'agenda_app'...\n";
        $pdo->exec("CREATE DATABASE agenda_app");
        echo "✅ Database 'agenda_app' created successfully!\n";
    }
    
    // Now connect to the specific database
    $pdo = new PDO('pgsql:host=tfe;port=5432;dbname=agenda_app', 'root', 'root');
    echo "✅ Connected to 'agenda_app' database successfully!\n";
    
    // Check existing tables
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "📋 No tables found. Ready for migrations.\n";
    } else {
        echo "📋 Found " . count($tables) . " existing tables:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    }
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check:\n";
    echo "1. PostgreSQL server is running on 'tfe:5432'\n";
    echo "2. User 'root' exists with password 'root'\n";
    echo "3. User has permission to create databases\n";
}
?>

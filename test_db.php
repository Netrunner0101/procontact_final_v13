<?php
try {
    $pdo = new PDO('pgsql:host=tfe;port=5432;dbname=agenda_app', 'root', 'root');
    echo "✅ Database connection successful!\n";
    
    // Check if tables exist
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "❌ No tables found in the database.\n";
    } else {
        echo "✅ Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    }
    
} catch(Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    
    // Try to connect without specifying database
    try {
        $pdo = new PDO('pgsql:host=tfe;port=5432', 'root', 'root');
        echo "✅ Server connection successful, but database 'agenda_app' doesn't exist.\n";
        
        // Create the database
        $pdo->exec("CREATE DATABASE agenda_app");
        echo "✅ Database 'agenda_app' created successfully!\n";
        
    } catch(Exception $e2) {
        echo "❌ Server connection also failed: " . $e2->getMessage() . "\n";
    }
}
?>

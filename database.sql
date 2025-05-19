
CREATE DATABASE IF NOT EXISTS student_store;
USE student_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('user', '1234');

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    image VARCHAR(100)
);

INSERT INTO products (name, description, price, image) VALUES
('Τσάντα', 'Ανθεκτική τσάντα πλάτης. Συνδυάζει αδιάβροχο ύφασμα με εξαιρετικό σχεδιασμό, διαθέτει ενσωματωμένη θήκη που ταιριάζει άνετα στους φορητούς υπολογιστές μέχρι 15,6 ίντσες, εσωτερικές τσέπες για τα απαραίτητα αξεσουάρ, τα βιβλία ή άλλα αντικείμενα.', 25.00, 'bag.jpg'),
('Λάπτοπ', 'Φορητός υπολογιστής κατάλληλος για επαγγελματική χρήση, απαιτητικά παιχνίδια, δημιουργικές εφαρμογές και multitasking. Αν ψάχνετε για ένα λάπτοπ με τις τελευταίες τεχνολογίες και υψηλότερες επιδόσεις είναι η καλύτερη επιλογή.', 950.00, 'laptop.jpg'),
('Τετράδιο', 'Τετράδιο ριγέ 100 σελίδων.', 2.00, 'notebook.jpg'),
('Στυλό', 'Μπλε στυλό διαρκείας.', 0.80, 'pen.jpg'),
('Κασετίνα', 'Κασετίνα υφασμάτινη με φερμουάρ.', 5.00, 'case.jpg');

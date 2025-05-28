INSERT INTO groups (id, name)
VALUES (1, 'Groep 1')

INSERT INTO users (name, username, email, password, role, group_id, access, created_at, updated_at)
VALUES (
'Admin','admin','admin@email.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaE0J1ZfH5Q2yLz1B3M4c7JbYyW', -- wachtwoord: "password"
'admin', 1, true, NOW(), NOW()
);



INSERT INTO users (name, username, password, email, group_id, role, access, email_verified_at, created_at, updated_at)
VALUES
-- Group 1
('Test Gebruiker 1', 'user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user1@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 2', 'user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user2@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 3', 'user3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user3@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 4', 'user4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user4@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 5', 'user5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user5@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 6', 'user6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user6@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 7', 'user7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user7@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 8', 'user8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user8@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 9', 'user9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user9@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 10', 'user10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user10@automatiseringhandhaving.nl', 1, 'user', 1, NOW(), NOW(), NOW()),

-- Group 2
('Test Gebruiker 11', 'user11', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user11@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 12', 'user12', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user12@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 13', 'user13', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user13@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 14', 'user14', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user14@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 15', 'user15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user15@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 16', 'user16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user16@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 17', 'user17', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user17@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 18', 'user18', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user18@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 19', 'user19', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user19@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 20', 'user20', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user20@automatiseringhandhaving.nl', 2, 'user', 1, NOW(), NOW(), NOW()),

-- Group 3
('Test Gebruiker 21', 'user21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user21@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 22', 'user22', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user22@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 23', 'user23', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user23@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 24', 'user24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user24@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 25', 'user25', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user25@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 26', 'user26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user26@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 27', 'user27', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user27@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 28', 'user28', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user28@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 29', 'user29', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user29@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW()),
('Test Gebruiker 30', 'user30', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa4p8YBoD69/ID8W1c1QF4NH8HG', 'user30@automatiseringhandhaving.nl', 3, 'user', 1, NOW(), NOW(), NOW());

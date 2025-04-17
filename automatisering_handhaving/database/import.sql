INSERT INTO groups (id, name)
VALUES (1, 'Groep 1')

INSERT INTO users (name, username, email, password, role, group_id, access, created_at, updated_at)
VALUES (
'Admin','admin','admin@email.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaE0J1ZfH5Q2yLz1B3M4c7JbYyW', -- wachtwoord: "password"
'admin', 1, true, NOW(), NOW()
);
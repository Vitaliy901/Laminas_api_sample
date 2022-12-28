CREATE TABLE users
(
    id bigserial,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    email varchar(128) NOT NULL,
    password varchar(80) NOT NULL,
    role varchar(70) NOT NULL,
    active timestamp,
    created_at timestamp,
    updated_at timestamp
);

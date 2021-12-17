create database php_blog;

create user 'php_blog_user'@'localhost' identified by 'php_blog_password';
grant all privileges on php_blog.* to 'php_blog_user'@'localhost';

use php_blog;

create table users (
    id int not null auto_increment primary key,
    username varchar(25) not null unique,
    password varchar(100) not null,
    name varchar(200),
    role varchar(10) not null default 'editor',
    email varchar(200),
    reset_link_token varchar(200),
    exp_date datetime
);

create table posts (
    id int not null auto_increment primary key,
    base_post_id int,
    language_id int,
    user varchar(25) not null,
    title varchar(200) not null,
    slug varchar(200),
    description text,
    body text,
    created_at datetime default now(),
    updated_at timestamp not null default current_timestamp on update current_timestamp,
    foreign key (user) references users(username) on delete cascade,
    foreign key (base_post_id) references posts(id) on delete cascade,
    foreign key (language_id) references languages(id) on delete cascade
);

alter table posts add ;
ALTER TABLE posts ADD FOREIGN KEY (base_post_id) REFERENCES posts(id);

alter table posts add ;
ALTER TABLE posts ADD FOREIGN KEY (language_id) REFERENCES languages(id) on delete cascade;

create table carousels_categories (
    id int not null auto_increment primary key,
    name varchar(100) not null unique
);

create table carousels (
    id int not null auto_increment primary key,
    category_id int not null,
    user varchar(25) not null,
    title varchar(200) not null unique,
    description text,
    created_at datetime default now(),
    updated_at timestamp not null default current_timestamp on update current_timestamp,
    foreign key (user) references users(username),
    foreign key (category_id) references carousels_categories(category_id)
);

create table images (
    id int not null auto_increment primary key,
    user varchar(25) not null,
    imgpath varchar(200) not null unique,
    caption varchar(250) not null,
    created_at datetime default now(),
    updated_at timestamp not null default current_timestamp on update current_timestamp,
    foreign key (user) references users(username)
);

create table carousels_images (
    carousel_id int,
    image_id int,
    foreign key (carousel_id) references carousels(id),
    foreign key (image_id) references images(id)
);

create table subscribers (
    email varchar(200) not null unique
);

create table languages (
    id int not null auto_increment primary key,
    name varchar(100) not null unique,
    prefix varchar(25) not null unique,
    is_default boolean not null default false
);

create table terms (
    id int not null auto_increment primary key,
    term varchar(100) not null unique
);

create table translations (
    id int not null auto_increment primary key,
    translation varchar(250) not null,
    term_id int not null,
    language_id int not null,
    foreign key (term_id) references terms(id) on delete cascade,
    foreign key (language_id) references languages(id) on delete cascade
);

-- User Seed
insert into users(username, password, name, role, email) values('admin', md5('admin'), 'Administrator', 'admin', 'vshlbndr@gmail.com');
insert into users(username, password, name, role, email) values('vishal', md5('vishal'), 'Vishal Bandre', 'editor', 'iamvshlbndr@gmail.com');

-- Posts Seed
insert into posts(user, title, description, body) values('admin', 'First Post', 'First Post Description', 'First Post Body');
insert into posts(user, title, description, body) values('vishal', 'Second Post', 'Second Post Description', 'Second Post Body');
insert into posts(user, title, description, body) values('vishal', 'Third Post', 'Third Post Description', 'Third Post Body');

-- Language Seed
insert into languages(name, prefix, is_default) values('English', 'en', true);
insert into languages(name, prefix, is_default) values('Marathi', 'mr', false);
insert into languages(name, prefix, is_default) values('Hindi', 'hi', false);

-- Multilingual Seeding
-- insert language id 1 for English
insert into posts(user, title, description, body, slug, base_post_id, language_id) values('admin', 'Marathi Title', 'Marathi Description', 'Marathi Content', 'marathi-title', 47, 9);
-- Insert language id 9 for Marathi
insert into posts(user, title, description, body, slug, base_post_id, language_id) values('admin', 'Marathi Title', 'Marathi Description', 'Marathi Content', 'marathi-title', 47, 9);
insert into posts(user, title, description, body, slug, base_post_id, language_id) values('admin', 'Hindi Title', 'Hindi Description', 'Hindi Content', 'hindi-title', 47, 4);

-- update posts table for default English language with language_id 1
update posts set language_id=1 where slug is null;


-- Export Database Schema Only
sudo mysqldump -u root -p --no-data php_blog > schema.sql

-- New features
-- 1. Adding new field for managing the user roles
-- 1.1 Added user role field to users table
alter table users add role varchar(10) not null default 'editor';
-- 1.2 Updated first registered user with admin role
update users set role='admin' where id=1;


-- 2. Forgot Password
-- 2.1. Adding email field to users table
alter table users add email varchar(200);
alter table users add reset_link_token varchar(200);
alter table users add exp_date datetime;

-- 3. Carousel
-- Adding fields to carousels table
alter table carousels add category_id int not null;
ALTER TABLE carousels ADD FOREIGN KEY (category_id) REFERENCES carousels_categories(id);

--4. Slug Field
alter table posts add slug varchar(200) unique;

--5. Added new table for Post Translations with two extra fields than Posts table
alter table posts add base_post_id int;
ALTER TABLE posts ADD FOREIGN KEY (base_post_id) REFERENCES posts(id);

alter table posts add language_id int;
ALTER TABLE posts ADD FOREIGN KEY (language_id) REFERENCES languages(id) on delete cascade;

-- Posts table altered to allow non unique title and slugs, because we were not able to insert null value, mysql was storing them as empty strings.
alter table posts drop index title;
alter table translations drop foreign key translations_ibfk_1;
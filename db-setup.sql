create database php_blog;

create user 'php_blog_user'@'localhost' identified by 'php_blog_password';
grant all privileges on php_blog.* to 'php_blog_user'@'localhost';

use php_blog;

create table users (
    id int not null auto_increment primary key,
    username varchar(25) not null unique,
    password varchar(100) not null,
    name varchar(200)
);

create table posts (
    id int not null auto_increment primary key,
    user varchar(25) not null,
    title varchar(200) not null unique,
    description text,
    body text,
    created_at datetime default now(),
    updated_at timestamp not null default current_timestamp on update current_timestamp,
    foreign key (user) references users(username)
);

insert into users(username, password, name) values('admin', md5('admin'), 'Administrator');
insert into users(username, password, name) values('vishal', md5('admin'), 'Editor');
insert into posts(user, title, description, body) values('admin', 'First Post', 'First Post Description', 'First Post Body');
insert into posts(user, title, description, body) values('vishal', 'Second Post', 'Second Post Description', 'Second Post Body');
insert into posts(user, title, description, body) values('vishal', 'Third Post', 'Third Post Description', 'Third Post Body');

-- New features
-- 1. Added user role field to users table
alter table users add role varchar(10) not null default 'editor';
-- 2. Updated first registered user with admin role
update users set role='admin' where id=1;
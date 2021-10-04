# Blog
### Instructions to setup the database

<pre>
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
    user varchar(25) not null,
    title varchar(200) not null unique,
    description text,
    body text,
    created_at datetime default now(),
    updated_at timestamp not null default current_timestamp on update current_timestamp,
    foreign key (user) references users(username)
);

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

insert into users(username, password, name, role, email) values('admin', md5('admin'), 'Administrator', 'admin', 'vshlbndr@gmail.com');
insert into users(username, password, name, role, email) values('vishal', md5('vishal'), 'Vishal Bandre', 'editor', 'iamvshlbndr@gmail.com');
insert into posts(user, title, description, body) values('admin', 'First Post', 'First Post Description', 'First Post Body');
insert into posts(user, title, description, body) values('vishal', 'Second Post', 'Second Post Description', 'Second Post Body');
insert into posts(user, title, description, body) values('vishal', 'Third Post', 'Third Post Description', 'Third Post Body');
</pre>

### Periodic jobs
# Setting cron jobs with crontab
<pre>
> crontab -e
# crontag content:
# Output to file
# */1 * * * * php -q /home/vish/dev/web/php/blog/newsletters/cron.php > /home/vish/dev/web/php/blog/logs/newsletters.txt

# Run script with php command
# Append to file
#*/1 * * * * php -q /home/vish/dev/web/php/blog/newsletters/cron.php >> /home/vish/dev/web/php/blog/logs/newsletters.txt

# Will give access to $_SERVER
#*/1 * * * * curl -q http://blog/newsletters/cron.php >> /home/vish/dev/web/php/blog/logs/newsletters.txt


# Check your cronjob activity with:
> /var/log$ grep CRON /var/log/syslog

e.g. vish@ubuntu:/var/log$ grep CRON /var/log/syslog
</pre>
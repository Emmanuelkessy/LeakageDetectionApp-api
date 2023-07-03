create database if not exists leakage_detection_db;
use leakage_detection_db;

create table if not exists user(
    user_id int not null auto_increment,
    first_name varchar(30) not null,
    last_name varchar(30) not null,
    phone varchar(16) not null,
    email varchar(40) not null,
    position varchar(20) not null,
    user_password varchar(255) not null,
    Primary key(user_id)
);

create table if not exists zone (
    zone_name varchar(20) not null,
    zone_location varchar(20),
    user_id int,
    Primary key (zone_name),
    Foreign key (user_id) references user(user_id)
);

create table if not exists sensors (
    sensor_name varchar(5) not null,
    sensor_location varchar(15) not null,
    zone_name varchar(20),
    Primary key (sensor_name),
    Foreign key (zone_name) references zone(zone_name)
);

create table if not exists main_tank (
    main_tank_data_id int not null auto_increment,
    sensor_name varchar(5),
    flow_rate decimal(5,2),
    volume decimal(5,2),
    time_stamp Time,
    Primary key (main_tank_data_id),
    Foreign key (sensor_name) references sensors(sensor_name)
);

create table if not exists branch_tank (
    branch_tank_data_id int not null auto_increment,
    sensor_name varchar(5),
    flow_rate decimal(5,2),
    volume decimal(5,2),
    time_stamp time,
    Primary key (branch_tank_data_id),
    Foreign key (sensor_name) references sensors(sensor_name)
);

create table if not exists leakage (
    leakage_id int not null auto_increment,
    branch_tank_data_id int,
    Primary key (leakage_id),
    Foreign key (branch_tank_data_id) references branch_tank(branch_tank_data_id)
);






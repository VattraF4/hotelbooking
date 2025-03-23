# DROP DATABASE IF EXISTS `hotel-booking`;
CREATE DATABASE IF NOT EXISTS `hotel-booking`;

USE `hotel-booking`;

# Create user Table
CREATE TABLE user
(
    id          INT(10) AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(200)                          NOT NULL,
    phone       VARCHAR(200)                          NOT NULL,
    email       VARCHAR(200)                          NOT NULL,
    my_password VARCHAR(200)                          NOT NULL,
    create_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL
);
INSERT INTO bookings(email, phone_number, hotel_name, room_name, room_id, user_id, check_in, check_out, create_at)
VALUES ('ravattrasmartboy@gmail.com',
        '0975361899',
        'The Plaza Hotel',
        'Suite Room',
        1,
        1,
        '2023-01-01',
        '2023-01-02',
        CURRENT_TIMESTAMP);
#If  phone is not null we use update and set can't not insert
# UPDATE user
# SET phone = '0975361899'
# WHERE email = 'ravattrasmartboy@gmail.com';


#Create `hotels` table
CREATE TABLE hotels
(
    id          INT(10) auto_increment NOT NULL PRIMARY KEY,
    name        VARCHAR(255)           NOT NULL,
    image       VARCHAR(255)           NOT NULL,
    description TEXT                   NOT NULL,
    location    VARCHAR(255)           NOT NULL,
    status      INT(5)                 NOT NULL DEFAULT 1,
    create_at   TIMESTAMP                       DEFAULT CURRENT_TIMESTAMP() NOT NULL ON UPDATE CURRENT_TIMESTAMP()
);
INSERT INTO hotels (name, image, description, location, status)
VALUES ('Sheraton', 'services-1.jpg', 'Even the all-powerful Pointing has no control about the blind texts it is an almost
							unorthographic.', 'Cairo', 1),
       ('The Plaza Hotel', 'image_4.jpg', 'Even the all-powerful Pointing has no control about the blind texts it is an almost
							unorthographic.', 'New York', 1),
       ('The Ritz', 'image_4.jpg', 'Even the all-powerful Pointing has no control about the blind texts it is an almost
							unorthographic.', 'Paris', 1);
#Insert a column name status in to hotels table with default value 1
# ALTER TABLE hotels
#     ADD COLUMN status INT(5) NOT NULL DEFAULT 1;#Create `rooms` table

create table rooms
(
    id         int(15) primary key                   not null auto_increment,
    name       varchar(255)                          not null,
    images     varchar(255)                          not null,
    num_person int(255)                              not null,
    size       int(15)                               not null,
    view       varchar(255)                          not null,
    num_bed    int(15)                               not null,
    hotel_id   int(15)                               not null,
    hotel_name varchar(255)                          not null,
    status     int(1)    default 1                   not null,
    create_at  timestamp default current_timestamp() not null
);
INSERT INTO rooms (name, images, num_person, size, view, num_bed, hotel_id, hotel_name, status)
VALUES ('Suite Room', 'room-1.jpg', 3, 45, 'Sea View', 1, 1, 'Sheraton', 1),
       ('Standard Room', 'room-2.jpg', 3, 60, 'Sea View', 2, 2, 'The Plaza Hotel', 1),
       ('Family Room', 'room-3.jpg', 4, 70, 'Sea View', 3, 3, 'The Ritz', 1),
       ('Deluxe Room', 'room-4.jpg', 5, 70, 'Sea View', 1, 1, 'Sheraton', 1);

CREATE TABLE utilities
(
    id          int(5) auto_increment primary key,
    name        varchar(200)                          not null,
    icon        varchar(200)                          not null,
    description text                                  not null,
    room_id     int(5)                                not null,
    create_at   timestamp default current_timestamp() not null
);
CREATE TABLE bookings
(
    id           INT(5) PRIMARY KEY                    NOT NULL AUTO_INCREMENT,
    email        VARCHAR(255)                          NOT NULL,
    phone_number VARCHAR(50)                           NOT NULL,
    hotel_name   VARCHAR(200)                          NOT NULL,
    room_name    VARCHAR(200)                          NOT NULL,
    room_id      INT(5)                                NOT NULL,
    user_id      INT(5)                                NOT NULL,
    check_in     DATE                                  NOT NULL,
    check_out    DATE                                  NOT NULL,
    create_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL
);
ALTER TABLE bookings
MODIFY
    check_in VARCHAR(200) NOT NULL ;

ALTER TABLE bookings
MODIFY
    check_out VARCHAR(200) NOT NULL ;


##----------INSERT DATA----------------##


INSERT INTO utilities (name, icon, description, room_id)
VALUES ('Tea Coffee', 'flaticon-diet',
        'A small river named Tole Sap River flows by their place and supplies it with the necessary', 1),
       ('Free WiFi', 'flaticon-workout', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 2),
       ('Kitchen', 'flaticon-diet-1', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 3),
       ('Ironing', 'flaticon-first', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 4),
       ('Lovkers', 'flaticon-first', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 4),
       ('Laundry', 'flaticon-diet-1', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 4),
       ('Air Conditioning', 'flaticon-first', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 4),
       ('Hot Showers', 'flaticon-workout', 'A small river named Tole Sap River flows by their place and supplies it with the necessary and
Even the all-powerful Pointing has no control about the blind texts it is an almost un-orthographic.', 4)
;




Omlook register example
========================

Migration sql
--------------

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DE8C6A357698A6A` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `user_role_assoc` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`roleId`),
  KEY `IDX_2B2819D264B64DCC` (`userId`),
  KEY `IDX_2B2819D2B8C2FD88` (`roleId`),
  CONSTRAINT `FK_2B2819D2B8C2FD88` FOREIGN KEY (`roleId`) REFERENCES `user_role` (`id`),
  CONSTRAINT `FK_2B2819D264B64DCC` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

INSERT INTO user_role (id, role, name, createdAt) VALUES (1, 'ROLE_ADMIN', 'Администратор', '2015-01-09 01:07:22');
INSERT INTO user_role (id, role, name, createdAt) VALUES (2, 'ROLE_CUSTOMER', 'Пользователь', '2015-01-09 01:07:39');
INSERT INTO user_role (id, role, name, createdAt) VALUES (3, 'ROLE_AUTHOR', 'Автор', '2015-01-09 02:37:23');
INSERT INTO user_role (id, role, name, createdAt) VALUES (4, 'ROLE_PUBLISHER', 'Издатель', '2015-01-09 02:37:58');

Documentation API
--------------

<b>Register user:</b>

method: POST
url: /api/v1/user/register
Параметры:
{
    "userRegister":
    {
        "email":"prolong@gmail.com",
        "password":"123456"
    }
}

<b>Login user:</b>

method: POST
url: /api/v1/user/login
Параметры:
{
    "userLogin":
    {
        "email":"prolong@gmail.com",
        "password":"123456"
    }
}

<b>Logout user:</b>

method: GET
url: /api/v1/logout
Параметры:


<b>Info user:</b>

method: GET
url: /api/v1/user/me
Параметры:


<b>Update user role:</b>

method: POST
url: /api/v1/admin/user/role
Параметры:
{
    "userUpdateRole":
    {
        "email":"prolong2@gmail.com",
        "roles":[
          {"role":"ROLE_AUTHOR"}
        ]
    }
}

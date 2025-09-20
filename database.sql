Create Table roupas(
    Id int primary key auto_increment,
    categoria varchar(25) NOT NULL,
    preco varchar(10) NOT NULL,
    disponivel bit default 1
);
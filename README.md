<p align="center"><a href="https://www.fteam.dev/?gad_source=1&gad_campaignid=22158513529&gbraid=0AAAAAoPeNEPDvsI5Xy5n4W-AuqE_g0JGF&gclid=Cj0KCQjwzaXFBhDlARIsAFPv-u-ixZs2o2P_QORHqAlkZn_Crrt-pSetKW7UC8p2kFfkOokyheWAxnwaAqgDEALw_wcB" target="_blank"><img src="https://instagram.fumu3-1.fna.fbcdn.net/v/t51.2885-19/462179899_3933016013611158_2829024752748095247_n.jpg?efg=eyJ2ZW5jb2RlX3RhZyI6InByb2ZpbGVfcGljLmRqYW5nby43NTAuYzIifQ&_nc_ht=instagram.fumu3-1.fna.fbcdn.net&_nc_cat=106&_nc_oc=Q6cZ2QEvM_p8TiEv60gXeGJZ3dobHAHhBX-YexDAIIg5m-HIqgxMZriMobHhpuK5789lww4&_nc_ohc=OdkMxJrS7dMQ7kNvwHjo8qv&_nc_gid=uX3hdbXMQYquayKSrX5gjw&edm=AP4sbd4BAAAA&ccb=7-5&oh=00_AfXp_obt1_Kh6J9m8KPHtwWOBuGlH51Hqg3JbdTrlnH5Ww&oe=68B5139F&_nc_sid=7a9f4b" width="400" alt="Laravel Logo"></a></p>

# sistema de agendamento

Esses sistema esta sendo desenvolvido para a instiruição do IAITEA(Instituto de Atendimento ao Indivíduo com Transtorno do Espectro Autista), ele visa realizar o agendamento para cada paciente

# Tecnologias

-Php/Laravel

-MySql

# Como rodar o projeto

clone o repositorio

``
git clone [Url do repositorio]
``

baixe as dependencias

``
composer i 
``

COnfigure a .env (exemplo usando o XAMPP para rodar)

``

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_DATABASE=[nome do banco]

DB_USERNAME=root

DB_PASSWORD=

``

rode as migrations

``
php artisan migrate
``

começe o projeto 

``
php artisan serve
``
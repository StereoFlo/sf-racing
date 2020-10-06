# описание

незаконченный проект на Symfony 5.1, в котором нет нужды

# Запуск

Docker:

```bash
docker-compose up --build
```
 ## Установка БД
 
 ```bash
./sctipts.sh robo db:reset
 ```
# Прочее

CS fixes
 ```bash
./sctipts.sh robo check:cs
 ```
 
Статический анализ

 ```bash
./sctipts.sh robo check:phpstan
 ```

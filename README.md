# Modern EQEmu Allaclone
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)![DaisyUI](https://img.shields.io/badge/daisyui-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white)

## 🚧 Work In Progress
This project is still under early active development and not all planned features are complete.

## Requirements

- PHP >= 8.2, Composer, Mysql/MariaDB, and an EQemu DB.

## Installation

[Download the item/spell icons!](https://github.com/chadw/modern-allaclone/releases/download/1.0.0/icons.zip) and unzip them to /public/img/icons

### To setup a local development environment
```
git clone https://github.com/chadw/modern-allaclone.git
cd modern-allaclone

composer install
npm install
npm run dev

cp .env.example .env
```

Edit the .env variables to point to your eqemu db.
```
EQEMU_DB_HOST=127.0.0.1
EQEMU_DB_PORT=3306
EQEMU_DB_DATABASE=peq
EQEMU_DB_USERNAME=user
EQEMU_DB_PASSWORD=password
```
### To set this up in production
First build the assets
```
npm run build
```
Then copy the /public/build/ folder to your production server.

Always install this outside your publically accessible web directory. Symlink the /public folder to your public accessible web directory.

## Screenshots

![global search](https://github.com/user-attachments/assets/928ad81d-bbd0-459e-90ab-c9a60879044a)

![zones](https://github.com/user-attachments/assets/186bb44c-d820-404e-b630-bcf993cdf114)

![zone view](https://github.com/user-attachments/assets/b8d27fe8-5037-4974-8d7b-988afa0d3a75)

![npc details](https://github.com/user-attachments/assets/194a897f-5123-4cae-a691-9c6c8a7d3862)

![item details](https://github.com/user-attachments/assets/eaef9979-d73b-4db0-aa7b-64d545f0d8c2)

![spell search and table view](https://github.com/user-attachments/assets/95cd93bf-9d93-4eb6-a924-012492a0c0d0)

![additional spell data](https://github.com/user-attachments/assets/feb15f5c-28c1-4acc-9f78-c10a47eacc70)

![item tooltips](https://github.com/user-attachments/assets/27fb0872-4765-4588-b414-0fd0f161e478)

![recipe search and details](https://github.com/user-attachments/assets/3ccb49ad-76f7-454b-86b6-ca8a2d5a145e)

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).

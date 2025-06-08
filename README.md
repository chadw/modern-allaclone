# Modern EQEmu Allaclone
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)![DaisyUI](https://img.shields.io/badge/daisyui-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white)

## 🚧 Work In Progress
This project is still under early active development and not all planned features are complete.

## Requirements

- PHP >= 8.2, Composer, Mysql/MariaDB, and an EQemu DB.

## Installation

To setup a local development environment
```
git clone https://github.com/chadw/modern-allaclone.git
cd modern-allaclone

composer install
npm install && npm run build

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
To set this up in production you'll want to do a few other things. Always install this outside your publically accessible web directory. Symlink the /public folder to your public accessible web directory.


## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).

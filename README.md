# Phit

## Introduction

This is phit that is a challenge implementing git with PHP for my understanding PHP and git. (still WIP) \
A project like [build-your-own-x](https://build-your-own-x.vercel.app/) and [code crafter](https://codecrafters.io/).

As you've probably noticed, "phit" is simply named by combining php and git.

## Tasks

- [x] init
- [ ] cat-file
  - [x] pretty print with option `p`
  - [ ] check type of object with option `t`
- [ ] add
  - [x] add file into index
  - [ ] remove deleted file from index
- [ ] commit
- [ ] log

## Usage

### install

needs to install dependencies. (only symfony/console)

```bash
composer install --no-dev
```

### init

init command creates `.phit` directory in current directory.

```bash
./phit init
```

### add

add command updates `.phit/index`. (now only adding files)

```bash
./phit add <filespec>
```

### cat-file

cat-file command uncompress object and print its content.

```bash
./phit cat-file -p <object-hash>
```

### commit

planning

### log

planning

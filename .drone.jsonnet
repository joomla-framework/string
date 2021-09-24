local volumes = [
    {
        name: "composer-cache",
        path: "/tmp/composer-cache",
    },
];

local hostvolumes = [
    {
        name: "composer-cache",
        host: {path: "/tmp/composer-cache"}
    },
];

local composer(phpversion, params) = {
    name: "composer",
    image: "joomlaprojects/docker-images:php" + phpversion,
    volumes: volumes,
    commands: [
        "php -v",
        "composer update " + params,
    ]
};

local phpunit(phpversion) = {
    name: "PHPUnit",
    image: "joomlaprojects/docker-images:php" + phpversion,
    [if phpversion == "8.1" then "failure"]: "ignore",
    commands: [
        "apt-get clean && apt-get  --allow-releaseinfo-change update && apt-get install -y locales",
        "locale -a",
        "localedef -c -i fr_FR -f UTF-8 fr_FR.UTF-8",
        "localedef -c -i ru_RU -f CP1251 ru_RU.CP1251",
        "locale -a",
        "vendor/bin/phpunit"
    ]
};

local pipeline(name, phpversion, params) = {
    kind: "pipeline",
    name: "PHP " + name,
    volumes: hostvolumes,
    steps: [
        composer(phpversion, params),
        phpunit(phpversion)
    ],
};

[
    {
        kind: "pipeline",
        name: "Codequality",
        volumes: hostvolumes,
        steps: [
            {
                name: "composer",
                image: "joomlaprojects/docker-images:php7.4",
                volumes: volumes,
                commands: [
                    "php -v",
                    "composer update",
                    "composer require phpmd/phpmd phpstan/phpstan"
                ]
            },
            {
                name: "phpcs",
                image: "joomlaprojects/docker-images:php7.4",
                depends: [ "composer" ],
                commands: [
                    "vendor/bin/phpcs --config-set installed_paths vendor/joomla/coding-standards",
                    "vendor/bin/phpcs -p --report=full --extensions=php --standard=ruleset.xml src/"
                ]
            },
            {
                name: "phpmd",
                image: "joomlaprojects/docker-images:php7.4",
                depends: [ "composer" ],
                failure: "ignore",
                commands: [
                    "vendor/bin/phpmd src text cleancode",
                    "vendor/bin/phpmd src text codesize",
                    "vendor/bin/phpmd src text controversial",
                    "vendor/bin/phpmd src text design",
                    "vendor/bin/phpmd src text unusedcode",
                ]
            },
            {
                name: "phpstan",
                image: "joomlaprojects/docker-images:php7.4",
                depends: [ "composer" ],
                failure: "ignore",
                commands: [
                    "vendor/bin/phpstan analyse src",
                ]
            },
            {
                name: "phploc",
                image: "joomlaprojects/docker-images:php7.4",
                depends: [ "composer" ],
                failure: "ignore",
                commands: [
                    "phploc src",
                ]
            },
            {
                name: "phpcpd",
                image: "joomlaprojects/docker-images:php7.4",
                depends: [ "composer" ],
                failure: "ignore",
                commands: [
                    "phpcpd src",
                ]
            }
        ]
    },
    pipeline("7.2 lowest", "7.2", "--prefer-stable --prefer-lowest"),
    pipeline("7.2", "7.2", "--prefer-stable"),
    pipeline("7.3", "7.3", "--prefer-stable"),
    pipeline("7.4", "7.4", "--prefer-stable"),
    pipeline("8.0", "8.0", "--prefer-stable"),
    pipeline("8.1", "8.1", "--prefer-stable")
]

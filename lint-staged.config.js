/** @type {import('lint-staged').Config} */
module.exports = {
  '*.php': [
    'php vendor/bin/parallel-lint src tests --colors --blame',
    'php vendor/bin/rector process --dry-run --ansi',
    'php vendor/bin/ecs check --fix',
  ],
  'composer.json': [
    'composer normalize --ansi'
  ],
};

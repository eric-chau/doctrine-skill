# Doctrine skill

Integration of Doctrine2 into Jarvis.

## To PHP 7.1 users

**You should add this dependency in your project root `composer.json`: ``"doctrine/common": "v2.7.1 as 2.6.99"``**. This is required to avoid proxy generation errors due to the new return type `void`.

You can learn more about this issue on [Doctrine Github issue#6000](https://github.com/doctrine/doctrine2/issues/6000).

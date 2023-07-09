<h1 align="center">Unofficial World Cube Association (WCA) Public API</h1>

<p align="center">
    <img src="swagger-ui/wca.png" 
         alt="WCA">
</p>

---

Welcome to the unofficial [World Cube Association](https://worldcubeassociation.org) (WCA) Public API documentation! 
Here, you'll find all the information you need to integrate this API seamlessly into your projects. 
Access competition data, results, competitor profiles, rankings, and more.

**Note**: This API is served through static json files on *GitHub*, that means the structure of the endpoints have limitations. 
The reason for doing so is:

* The data doesn't change that much, max once a day
* Static file based API is very fast
* I don't want to pay for any hosting because it would get very expensive, very fast

The API is updated once a day so rankings and results are not real-time.

> This information is based on competition results owned and maintained by the
> World Cube Association, published at https://worldcubeassociation.org/results
> as of <!--START_SECTION:version-date-->July 09, 2023<!--END_SECTION:version-date-->.

I'm in no way affiliated or part of the official WCA software team.

## Getting started

The full documentation and specs are available on
[https://wca-rest-api.robiningelbrecht.be/](https://wca-rest-api.robiningelbrecht.be/)

## Local development

If you'd like to help on the development of this project, or you just want to run un locally,
run following commands:

```bash
# Clone repo
> git clone git@github.com:robiningelbrecht/wca-rest-api.git
# Build docker containers
> docker-compose up -d --build
# Install dependencies
> docker-compose run --rm php-cli composer install
# Build all the static API files.
> docker-compose run --rm php-cli bin/build-new-api.sh "continent,country,event,competition,championship,person,rank,result,version"
```

### Test suite

When you've completed the local setup, you can run the test suite by running

```bash
> docker-compose run --rm php-cli vendor/bin/phpunit
```
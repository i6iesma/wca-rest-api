<h1 align="center">Unofficial World Cube Association (WCA) Public API</h1>

<p align="center">
    <img src="swagger-ui/wca.png" 
         alt="WCA">
</p>

---

Welcome to the unofficial [World Cube Association](https://worldcubeassociation.org) (WCA) Public API documentation! 
Here, you'll find all the information you need to integrate our API seamlessly into your projects. 
Access competition data, results, competitor profiles, rankings, and more.

**Note**: This API is served through static json files on *GitHub*, that means the structure of the endpoints have limitations. 
The reason for doing so is:

* The data doesn't change that much, max once a day
* Static file based API is very fast
* I don't want to pay for any hosting because it would get very expensive, very fast

> This information is based on competition results owned and maintained by the
> World Cube Association, published at https://worldcubeassociation.org/results
> as of <!--START_SECTION:version-date-->June 29, 2023<!--END_SECTION:version-date-->.

I'm in no way affiliated or part of the official WCA software team.

## Getting started

The full documentation and specs are available on
[https://wca-rest-api.robiningelbrecht.be/](https://wca-rest-api.robiningelbrecht.be/)

## Examples 

### General

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/continents.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/countries.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/events.json'
```

### Competitions

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions-page-2.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions/BE.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions/2023.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions/333.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions/333-page-2.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/competitions/BrizZonSylwesterOpen2023.json'
```

### Persons

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/persons.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/persons-page-2.json'
```

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/persons/2012PARK03.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/persons/max-park.json'
```

### Rank

```bash
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/rank/world/single/333.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/rank/BE/average/222.json'
curl --location 'https://raw.githubusercontent.com/robiningelbrecht/wca-rest-api/master/api/rank/europe/single/444.json'
```

TODO
----
* api/results/{competition}.json
* api/results/{competition}/{event}.json
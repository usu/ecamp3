/* datacontext: data access and model management layer */
app.factory('datacontext', function (logger, model, jsonResultsAdapter) {

    breeze.config.initializeAdapterInstance("modelLibrary", "backingStore", true);

    var serviceName = "http://www.ecamp3.dev/api/v0/"; // Ecamp

    var ds = new breeze.DataService({
        serviceName: serviceName,
        hasServerMetadata: false,
        jsonResultsAdapter: jsonResultsAdapter
    });

    var manager = new breeze.EntityManager({dataService: ds});

    model.initialize(manager.metadataStore);

    return {
        getCamps: getCamps,
        getEvents: getEvents
    };

    /*** implementation details ***/

    function getCamps() {
        // /camps
        var parameters = CampParameters({'mode':'all'});
        var query = breeze.EntityQuery
            .from("camps")
            .withParameters(parameters);
        return manager.executeQuery(query).then(returnResults);
    }

    function getEvents(Camp) {
        // /camps/id/events
        var parameters = CampParameters();
        var query = breeze.EntityQuery
            .from("camps/" + Camp.id + "/events")
            .withParameters(parameters);
        return manager.executeQuery(query).then(returnResults);
    }

    function CampParameters(addlParameters) {
        var parameters = {
            /* add general paramters here */
        };
        return breeze.core.extend(parameters, addlParameters);
    }

    function returnResults(data){return data.results}

});
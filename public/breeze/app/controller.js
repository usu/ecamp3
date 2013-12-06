app = angular.module('app', [])

/* Defines the "Ecamp" controller 
 * Constructor function relies on Ng injector to provide:
 *     $scope - context variable for the view to which the view binds
 *     datacontext - the apps data access facility
 *     logger - logs controller activities during development
 */
app.controller('EcampCtrl', function ($scope, datacontext, logger) { 

    $scope.searchText = "";
    $scope.camps = [];
    $scope.getCamps = getCamps;
    $scope.getEvents = getEvents;
    $scope.getCamps();

    //#region private functions

    function getCamps() {
        datacontext.getCamps().then(succeeded).fail(queryFailed);

        function succeeded(results) {
            $scope.camps = results;
            $scope.$apply();
            logger.info("Fetched " + results.length + " Camps");
        }
    };

    function getEvents(camp) {
    	
    	if (!camp.showEvents) {
            return; // don't bother if not showing
        } else if (camp.events.length > 0) {
            // already in cache; no need to get them
            logGetEventResults(true /*from cache*/);
        } else {
            getEventsFromEcamp()
        }

        function getEventsFromEcamp() {
            camp.isLoading = true;
            datacontext.getEvents(camp)
                .then(succeeded).fail(queryFailed).fin(done);

            function succeeded(data) {
                // Events automatically link up with Camps via fk              
                logGetEventResults(false /*from web*/);
            }

            function done() {
                camp.isLoading = false;
                $scope.$apply();
            }
        }

        function logGetEventResults(fromCache) {
            var src = fromCache ? 'from cache' : 'via web service call';
            logger.info("Fetched "+src+": " + camp.events.length + " Events for " + camp.name);
        }
    };

    function queryFailed(error) {
        logger.error(error.message, "Query failed; please try it again.");
    }
   
    //#endregion
});
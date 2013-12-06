/* jsonResultsAdapter: parses Ecamp data into entities */
app.value('jsonResultsAdapter', 
    new breeze.JsonResultsAdapter({

        name: "Ecamp",

        extractResults: function (data) {
            var results = data.results;
            if (!results) throw new Error("Unable to resolve 'results' property");
            
            return results._embedded.items;
        },

        visitNode: function (node, parseContext, nodeContext) {
        	
        	resource = parseContext.query.resourceName; 
        	
            // Camp parser
            if (resource == 'camps' ) {
                return { entityType: "Camp"  }
            }
            
            // Event parser
            if (resource.substr(resource.length - 6) == 'events' ) {
            	return { entityType: "Event"  }
            }
        
        }

    }));

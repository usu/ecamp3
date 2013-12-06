/* model: entity definitions */
app.factory('model', function () {
    var DT = breeze.DataType; // alias
    return {
        initialize: initialize
    }

    function initialize(metadataStore) {
        metadataStore.addEntityType({
            shortName: "Camp",
            namespace: "Ecamp",
            dataProperties: {
                id:         { dataType: DT.String, isPartOfKey: true },
                name:       { dataType: DT.String },
                title:      { dataType: DT.String },
                start:      { dataType: DT.String },
                end:        { dataType: DT.String },
            },
            navigationProperties: {
                events: {
                    entityTypeName:  "Event:#Ecamp", isScalar: false,
                    associationName: "Camp_Events"
                }
            }
        });

        metadataStore.addEntityType({
            shortName: "Event",
            namespace: "Ecamp",
            dataProperties: {
                id:            { dataType: "String", isPartOfKey: true },
                camp_id:       { dataType: "String", },
                title:         { dataType: "String" },
            },
            navigationProperties: {
                camp: {
                    entityTypeName:  "Camp:#Ecamp", isScalar: true,
                    associationName: "Camp_Events",  foreignKeyNames: ["camp_id"]
                }
            }
        });
    }
})
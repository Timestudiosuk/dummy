<?php
namespace Aws\SSMIncidents;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Systems Manager Incident Manager** service.
 * @method \Aws\Result createReplicationSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createReplicationSetAsync(array $args = [])
 * @method \Aws\Result createResponsePlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createResponsePlanAsync(array $args = [])
 * @method \Aws\Result createTimelineEvent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createTimelineEventAsync(array $args = [])
 * @method \Aws\Result deleteIncidentRecord(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteIncidentRecordAsync(array $args = [])
 * @method \Aws\Result deleteReplicationSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteReplicationSetAsync(array $args = [])
 * @method \Aws\Result deleteResourcePolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteResourcePolicyAsync(array $args = [])
 * @method \Aws\Result deleteResponsePlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteResponsePlanAsync(array $args = [])
 * @method \Aws\Result deleteTimelineEvent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteTimelineEventAsync(array $args = [])
 * @method \Aws\Result getIncidentRecord(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getIncidentRecordAsync(array $args = [])
 * @method \Aws\Result getReplicationSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getReplicationSetAsync(array $args = [])
 * @method \Aws\Result getResourcePolicies(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResourcePoliciesAsync(array $args = [])
 * @method \Aws\Result getResponsePlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResponsePlanAsync(array $args = [])
 * @method \Aws\Result getTimelineEvent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTimelineEventAsync(array $args = [])
 * @method \Aws\Result listIncidentRecords(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listIncidentRecordsAsync(array $args = [])
 * @method \Aws\Result listRelatedItems(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRelatedItemsAsync(array $args = [])
 * @method \Aws\Result listReplicationSets(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listReplicationSetsAsync(array $args = [])
 * @method \Aws\Result listResponsePlans(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listResponsePlansAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTimelineEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTimelineEventsAsync(array $args = [])
 * @method \Aws\Result putResourcePolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putResourcePolicyAsync(array $args = [])
 * @method \Aws\Result startIncident(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startIncidentAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDeletionProtection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDeletionProtectionAsync(array $args = [])
 * @method \Aws\Result updateIncidentRecord(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateIncidentRecordAsync(array $args = [])
 * @method \Aws\Result updateRelatedItems(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateRelatedItemsAsync(array $args = [])
 * @method \Aws\Result updateReplicationSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateReplicationSetAsync(array $args = [])
 * @method \Aws\Result updateResponsePlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateResponsePlanAsync(array $args = [])
 * @method \Aws\Result updateTimelineEvent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateTimelineEventAsync(array $args = [])
 */
class SSMIncidentsClient extends AwsClient {}
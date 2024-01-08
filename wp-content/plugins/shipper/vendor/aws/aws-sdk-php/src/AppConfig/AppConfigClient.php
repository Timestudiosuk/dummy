<?php
namespace Aws\AppConfig;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon AppConfig** service.
 * @method \Aws\Result createApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createApplicationAsync(array $args = [])
 * @method \Aws\Result createConfigurationProfile(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createConfigurationProfileAsync(array $args = [])
 * @method \Aws\Result createDeploymentStrategy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDeploymentStrategyAsync(array $args = [])
 * @method \Aws\Result createEnvironment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createEnvironmentAsync(array $args = [])
 * @method \Aws\Result createExtension(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createExtensionAsync(array $args = [])
 * @method \Aws\Result createExtensionAssociation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createExtensionAssociationAsync(array $args = [])
 * @method \Aws\Result createHostedConfigurationVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createHostedConfigurationVersionAsync(array $args = [])
 * @method \Aws\Result deleteApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteApplicationAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationProfile(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteConfigurationProfileAsync(array $args = [])
 * @method \Aws\Result deleteDeploymentStrategy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDeploymentStrategyAsync(array $args = [])
 * @method \Aws\Result deleteEnvironment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteEnvironmentAsync(array $args = [])
 * @method \Aws\Result deleteExtension(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteExtensionAsync(array $args = [])
 * @method \Aws\Result deleteExtensionAssociation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteExtensionAssociationAsync(array $args = [])
 * @method \Aws\Result deleteHostedConfigurationVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteHostedConfigurationVersionAsync(array $args = [])
 * @method \Aws\Result getApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getApplicationAsync(array $args = [])
 * @method \Aws\Result getConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getConfigurationAsync(array $args = [])
 * @method \Aws\Result getConfigurationProfile(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getConfigurationProfileAsync(array $args = [])
 * @method \Aws\Result getDeployment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDeploymentAsync(array $args = [])
 * @method \Aws\Result getDeploymentStrategy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDeploymentStrategyAsync(array $args = [])
 * @method \Aws\Result getEnvironment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getEnvironmentAsync(array $args = [])
 * @method \Aws\Result getExtension(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getExtensionAsync(array $args = [])
 * @method \Aws\Result getExtensionAssociation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getExtensionAssociationAsync(array $args = [])
 * @method \Aws\Result getHostedConfigurationVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getHostedConfigurationVersionAsync(array $args = [])
 * @method \Aws\Result listApplications(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \Aws\Result listConfigurationProfiles(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listConfigurationProfilesAsync(array $args = [])
 * @method \Aws\Result listDeploymentStrategies(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDeploymentStrategiesAsync(array $args = [])
 * @method \Aws\Result listDeployments(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDeploymentsAsync(array $args = [])
 * @method \Aws\Result listEnvironments(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listEnvironmentsAsync(array $args = [])
 * @method \Aws\Result listExtensionAssociations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listExtensionAssociationsAsync(array $args = [])
 * @method \Aws\Result listExtensions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listExtensionsAsync(array $args = [])
 * @method \Aws\Result listHostedConfigurationVersions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listHostedConfigurationVersionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startDeployment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDeploymentAsync(array $args = [])
 * @method \Aws\Result stopDeployment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopDeploymentAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateApplicationAsync(array $args = [])
 * @method \Aws\Result updateConfigurationProfile(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateConfigurationProfileAsync(array $args = [])
 * @method \Aws\Result updateDeploymentStrategy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDeploymentStrategyAsync(array $args = [])
 * @method \Aws\Result updateEnvironment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateEnvironmentAsync(array $args = [])
 * @method \Aws\Result updateExtension(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateExtensionAsync(array $args = [])
 * @method \Aws\Result updateExtensionAssociation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateExtensionAssociationAsync(array $args = [])
 * @method \Aws\Result validateConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise validateConfigurationAsync(array $args = [])
 */
class AppConfigClient extends AwsClient {}
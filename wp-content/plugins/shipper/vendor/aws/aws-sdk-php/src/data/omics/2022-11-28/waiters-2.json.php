<?php
// This file was auto-generated from sdk-root/src/data/omics/2022-11-28/waiters-2.json
return [ 'version' => 2, 'waiters' => [ 'AnnotationImportJobCreated' => [ 'description' => 'Wait until an annotation import is completed', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetAnnotationImportJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], ], ], 'AnnotationStoreCreated' => [ 'description' => 'Wait until an annotation store is created', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetAnnotationStore', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'ACTIVE', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CREATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'UPDATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], ], ], 'AnnotationStoreDeleted' => [ 'description' => 'Wait until an annotation store is deleted.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetAnnotationStore', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'DELETED', ], [ 'matcher' => 'error', 'state' => 'success', 'expected' => 'ResourceNotFoundException', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'DELETING', ], ], ], 'ReadSetActivationJobCompleted' => [ 'description' => 'Wait until a job is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetReadSetActivationJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CANCELLING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'COMPLETED_WITH_FAILURES', ], ], ], 'ReadSetExportJobCompleted' => [ 'description' => 'Wait until a job is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetReadSetExportJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CANCELLING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'COMPLETED_WITH_FAILURES', ], ], ], 'ReadSetImportJobCompleted' => [ 'description' => 'Wait until a job is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetReadSetImportJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CANCELLING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'COMPLETED_WITH_FAILURES', ], ], ], 'ReferenceImportJobCompleted' => [ 'description' => 'Wait until a job is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetReferenceImportJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CANCELLING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'COMPLETED_WITH_FAILURES', ], ], ], 'RunCompleted' => [ 'description' => 'Wait until a run is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetRun', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'PENDING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STARTING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'RUNNING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STOPPING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], ], ], 'RunRunning' => [ 'description' => 'Wait until a run is running.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetRun', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'RUNNING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'PENDING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STARTING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], ], ], 'TaskCompleted' => [ 'description' => 'Wait until a task is completed.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetRunTask', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'PENDING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STARTING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'RUNNING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STOPPING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], ], ], 'TaskRunning' => [ 'description' => 'Wait until a task is running.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetRunTask', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'RUNNING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'PENDING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'STARTING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'CANCELLED', ], ], ], 'VariantImportJobCreated' => [ 'description' => 'Wait until variant import is completed', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetVariantImportJob', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'SUBMITTED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'IN_PROGRESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'CANCELLED', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'COMPLETED', ], ], ], 'VariantStoreCreated' => [ 'description' => 'Wait until a variant store is created', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetVariantStore', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'ACTIVE', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CREATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'UPDATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], ], ], 'VariantStoreDeleted' => [ 'description' => 'Wait until a variant store is deleted.', 'delay' => 30, 'maxAttempts' => 20, 'operation' => 'GetVariantStore', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'DELETED', ], [ 'matcher' => 'error', 'state' => 'success', 'expected' => 'ResourceNotFoundException', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'DELETING', ], ], ], 'WorkflowActive' => [ 'description' => 'Wait until a workflow is active.', 'delay' => 3, 'maxAttempts' => 10, 'operation' => 'GetWorkflow', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'ACTIVE', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'CREATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'retry', 'expected' => 'UPDATING', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'failure', 'expected' => 'FAILED', ], ], ], ],];
### A genieacs api helper class for php

#### Configuration

Open service.php and edit `const URL = 'http://__SOMETHING__:7557';` line to your api endpoint. Yes thats the config

-----

#### Methods
* `getAllDevices()`: Returns all device records as array.
* `getDeviceById(string $device_uuid)`: Returns specific device by given id. Usually its a [UUIDv4](https://en.wikipedia.org/wiki/Universally_unique_identifier)
* `getDevicesByQuery(array $query)`: Returns devices that matches the query array eg `$query = ['id' => 'caf73be0-ee11-40dd-92aa-f6f2e3d387a1']`
* `getDevicesByTags(array $tags)`: Returns all devices that matches the given tags array.
* `deleteDevice(string $device_uuid)`: Simply deletes the specified device.
* `addTag(string $device_uuid, string $tag)`: Adds a tag to the device.
* `removeTag(string $device_uuid, string $tag)`: Removes the tag from device.
* `getFiles()`: Returns uploaded firmware files as array.
* `uploadFile(string $local_path, string $filename)`: Uploads the given `$local_path` file to the server.
  - $local_path`: Local fullpath of the target file
  - $filename`: Name of the file in the server.
* `deleteFile(string $filename)`: Deletes the file from server.
* `faults(string $device_uuid)`: Returns faults records for a specific device.
* `getParameterValues(string $parameters, string $device_uuid)`: Returns specified paramters from the specified device. Possible parameters are located at `./parameters.php`
* `setParameterValues(string $parameters, string $device_uuid)`: Changes given devices parameter. $paramters should be key value pair.
* `refreshObject(string $parameters, string $device_uuid)`: Reloads the value of the given parameter from actual device.
* `refreshAllObjects(string $device_uuid)`: Reloads all values of the given device.
* `reboot(string $device_uuid)`: Reboots the device.
* `factoryReset(string $device_uuid)`: Makes a factory reset request to device.
* `pendingTasks(string $device_uuid)`: Get awaiting tasks of the device.
* `getParameters(array|string $parameters, string $device_uuid)`: Get specified parameters of the device.
* `getTasks(string $device_uuid)`: Get all tasks of the device.
* `dispatchAction(string $op, string $device_uuid)`: Dispatch an action on the device. $op must be `reset`, `reboot`, `delete` or `refresh`.
* `deleteTask(string $device_uuid, string $task_id)`: Delete the given task. No matter what would be the outcome of this ruthless action.

𝒯𝒽𝒶𝓉'𝒶 𝒶𝓁𝓁 𝒻𝑜𝓁𝓀𝓈...

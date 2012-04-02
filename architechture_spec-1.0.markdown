##Subsystems

- Core Subsystem
- HTTP Subsystem
- Data Subsystem
- Extractable Subsystem



### Core Subsystem
It includes following classes 

1. **Biriani**. This is the main programming interface of Biriani. Any applicaiton
should instantiate this class and set the resource url. After that it should try
to fetch data or get the extractor (see IExtractable) object. An extractor object 
can later be used to access the data.
2. **Exception classes**. There are some custom exception classes used in Biriani. 
The name of exception classes should explain the purpose.

    - **`BirianiMatchedExtractableNotFoundException`**. Thrown when extractable to 
parse the current data is not found in the registry (see Biriani_Registry)
    - **`BirianiUncompltedRequestObjectException`**. Thrown when any request made for 
the resource url is not in completed yet but trying to parse data with response. 
    - **`BirianiRequiredExtensionNotFoundException`**. Thrown when any required php 
extension is not found. This exception should not be used. Because if the php 
extension is not there it should be installed first

3. **Biriani_Registry**. Provies simple interface to check whether any extractable 
exists and how to include it in the current file. It also contains all the 
extractables in `$services` class variable.

4. **Biriani_Data**. All the data extracted by proper extractable returned as 
`Biriani_Data`. This class (or its subclasses) holds all the aspects of extracted data.

Here is an overview on how to use `Biriani`.

    $biriani = new Biriani();
    $biriani->set_cache_duration(3600);
    $biriani->set_cache_locatio('/tmp');
    $biriani->set_url("https://github.com/shiplu.atom");
    $biriani->execute();
    $data = $biriani->fetch_data(); // returnes Biriani_Data

    // shortcut method
    $data = $biriani->fetch("https://github.com/shiplu.atom"); // returnes Biriani_Data

### HTTP Subsystem
This subsystem is used to make http request and provides a way to use the response 
returned from it. Usually HTTP request are made by `Biriani_Request` object. After 
executing the request it returnes a `Biriani_Response` object. Here are the classes 
used in HTTP subsystem

1. **Biriani_HTTPTransaction**. Contains all the common parameters and behaviors of 
a HTTP transaction. It can be a request or respose.
2. **Biriani_Request**. The request object. Used to invoke an HTTP request
3. **Biriani_Response**. The response object. Used to contain an HTTP response. 
Usually this object is returned from `Biriani_Request` after a successfull request.

####Class hierarchy.

- `Biriani_HTTPTransaction`
    - `Biriani_Request`
    - `Biriani_Response`

Here is an over view on how to make an http request and get response

    $req = new Biriani_Request("http://xkcd.com/221");
    $req->set_request_type(Biriani_Request::BIRIANI_REQUEST_GET);
    $res = $req->run(); // returns Biriani_Response


### Extractor subsystem
This subsystem is responsible for extracting vital data from a file.  All extractable 
classes implements `IExtractable` interface. The abstruct class 
`Biriani_Extractable_Abstract` should provide a factory method that determines which 
of its subclass would be instanciated. The logic to determine the proper Extractor is 
in this class. 

`Biriani_Extractable_Abstract` should implement `IExtractable` and all Extractable 
classes should extend `Biriani_Extractable_Abstract` class.

####Class hierarchy.

- `IExtractable`. 
    - `Biriani_Extractable_Abstract`
        - `FeedBiriani`
        - `TwitterBiriani` (not implemented)
        - `WordpressBiriani` (not implemented)


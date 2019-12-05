# Seaport Recommendation Application
A comprehensive seaport recommendation application!

### Live Demo URL: http://ba-app.xtremeonecoder.com/

## Project Analysis

### Decision Criteria:

### Port Draft:
This is an important criteria that container ships has to consider if their ship size is huge. Deeper draft attracts bigger ships.

### Number of Berth:
Number of berth in a port can affect the waiting time of the ships and higher number of berth in a port can reduce the traffic in that port.

### Capacity of Port Facilities:
Higher capacity of port facility can reduce the cargo handling time in the port.

### Port Location:
This is one of the most important criteria for ship containers. The port location can reduce the cost and turnaround of the container ships.

### Port Technology:
QC or yard cranes with higher speeds or modern and faster AGVs and trucks in the port will reduce the time and costs in the port.

### Operating Time:
The operating time in a port depends on many factors e.g. the port's traffic, port equipment, QC efficiency and speed but the situation of the container ships can affect it as well like the number of containers on a ship, size of the ship and etc.

### Port's Safety:
This criteria can be important for ships as well. Suppose a vessel carry flammable materials and can be dangerous if they don't consider the target port's safety.

### Operating Cost:
Like operating time, Operating cost is very important. As we know, Most of the times it's a matter of time and cost.

### Vessel Maintenance:
Larger drydock in the port or better repair services means higher value for this criteria.

### Port Service:
Services such as clearance (for all types of vessels) and full port operations service from nomination up to final DA (pre-arrival management, in port service, post departure follow up).

### International Policies:
Every country has foreign policy which is also called foreign relations or foreign affairs policy. These policies includes seaports as well. Each seaport in different countries has their own international policies. If the international policies in a seaport is strict then it gets lower values otherwise it get higher values.

### Night Navigation:
Port with night navigation system will help large vessels for berthing and handling through the night and it will help the port as well to work 24/7.

### Port Management:
Port management playing a very important role in ports. Ports need to deal with so many different activities like loading and unloading, allocating berth to ships, managing human resources, tugs, cranes and so on and without a good management it is not possible and they will face real problems.

### Port Labour:
Port Labour is one of the most important aspects which can directly impact on time and cost of the shipment at sea-ports. As it includes port workers ability and efficiency to load, unload and positioning the merchandise on a ship swiftly with safely as well as the capability of properly utilize the available logistics of the port.

### Custom Formalities:
Custom formalities at sea-port can both negatively as well as positively impact on shipment time and cost as well as the interest of choosing the port for shipment. For example, if the formalities are more strict and lengthy, such as - *Document Receiving -> Cargo Examination -> Classification -> Taxation -> Release / Withdraw*, then importers and exporters may not choose the port for their business.

## Project Design:
The diagram below shows an abstract network model view of the application. Some third party tools are used to develop the application, those are as follows:

1) PHP, a powerful server side scripting language for internet programming
2) Zend Framework, a PHP framework for developing the core application.
3) MySQL, a database management tool for medial-scale application evelopment.
4) CanvasJS, a javascript and php tool for implementing chart / graph.
5) SimpleXLSX Master, a php tool for handling excel data.
6) jQuery, a javascript library for advanced application development.
7) jQuery UI, a javascript library for advanced UI development.
8) Bootstrap, an advanced CSS framework for professional application development.
9) XHTML
10) CSS

![This network model shows the total process that happens in our application](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Network-Model.jpg)

## Project Implementation:
The algorithm used for implementing this project is decision matrix algorithm. In simple decision matrix, the strategy is using some fixed weights for the decision criteria and then based on the decision criteria tried to assign scores to the different objects and at last tried to calculate, but in this case, thing was a little bit different. In this application two data tables were used:

*1. Ships Data*
*2. Ports Data*

### Ships Data:
This table consists the scores for each ship that users give to each decision criteria. As it is said earlier that 15 criteria were founded that seemed to be very important for selecting a container seaport. Each ship has been assigned scores to the criteria based on their priorities. For example, if for a ship operating cost and time are highly important so they will give higher scores to these two criteria and these scores will act as the weights in calculating the total value for each port.

![Evaluation scores for each ship](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/ship-data.jpg)

### Ports Data:
On the other side there were ports data which were evaluated on the ports. It is tried to evaluate each port based on each criteria. For example, how high are the costs in the ports or how good is their night navigation system. It is tried to find data for each port. For some criteria it was easy to find data but there were some criteria that were not easy to get information about them from searching on the internet or from papers. So in these situations, there was no other choice but making assumptions.

![Evaluation scoresear for each port](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/port-data.jpg)

And after getting the data, it is tried to calculate the total scores for each ship. The calculation process is as below:

![Calculating weight per criteria for all the ports](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Process-Step-1.jpg)

![Collecting weight per criteria for all the vessels](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Process-Step-2.jpg)

![Multiply each ship criteria weight with corresponding port criteria weight](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Process-Step-3-4-5.jpg)

![Calculate function is implemented here for evaluating maximum score](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Algorithm-Implementation-1.jpg)

![Calculate function is invoked here and evaluated maximum score](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Algorithm-Implementation-2.jpg)

Base on evaluation of the ports and existing data of the ships, this DSS will select the best port for each ship.

![This chart shows the output results for 40 ships](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Result-in-Chart.jpg)

## Sample Port Weight Calculation:
Suppose, there is a criteria “Operating Cost” at the port. If the cost is high the port will get lower grade for that criteria and vice versa.

### Sample Questionnaire (Port users / civil people may answer):

**Q:** _What extent do you think “Port-A” provides good / bad “Operating Cost”?_

_A. Extremely good / Excellent (10)_

_B. Very good (9)_

_C. Yes, it is good (8)_

_D. Moderately good (7)_

_E. It is good, but less (6)_

_F. Maybe good, not sure (5)_

_G. I do not want to say (4)_

_H. Not good or it is bad (3)_

_I. Very bad (2)_

_J. Extremely bad (1)_

Suppose, we surveyed on 10 people and the results of the survey of Port-A for “Operating Cost” are as follows:

![Sample Port Weight Calculation](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Port-Data-Calculation.jpg)

## Sample Ship Weight Calculation:
Suppose for the same criteria “Operating Cost” at the port, if the “Operating Cost” at the port is an important aspect for the vessel manager or authority, it will get high priority and vice versa.

### Sample Questionnaire (Ship manage / authority may answer):

**Q:** _What extent “Operating Cost” is important for you?_

_A. Extremely important (10)_

_B. Very important(9)_

_C. Yes, it is important (8)_

_D. Moderately important (7)_

_E. It is important, but less (6)_

_F. Maybe important, not sure (5)_

_G. I do not want to say (4)_

_H. Not important (3)_

_I. It is irrelevant (2)_

_J. Extremely irrelevant (1)_

Suppose in 10 surveys, following results have been observed:

![Sample Ship Weight Calculation](https://github.com/xtremeonecoder/seaport-recommender/blob/master/Documentation/Ship-Data-Calculation.jpg)

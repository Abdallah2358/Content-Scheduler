# Backend
## Models & Database
1. \[Posts\] What does the PostPlatform's platform_status stand for ?
  - Assumptions: 
    1. Since this is an automation platform maybe the publishing fails so we need this status for error handling ?!
    2. May Be the platform allows for also hiding posts form certain platforms and this felid indicates the status of the post on the platform 
## Api EndPoints
1. \[Posts\]  does "Update a scheduled post" mean that the user can edit only draft and schedule posts ?
2. \[Platforms\]What does "Toggle active platforms for a user" mean is it related to platform_status from  PostPlatform table ??
  - Assumptions:
    1. The user has a missing relation with platforms ( Active platforms) which should indicate the platforms that the user Activated ( may mean that the user finished setting up the permissions of posting on his behalf) [ Makes Most Business wise sense for this scenario ]
    2. This will build on the 2nd Assumption of question 1 "May Be the platform allows for also hiding..." allowing the user to choose these status 


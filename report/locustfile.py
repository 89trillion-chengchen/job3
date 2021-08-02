import time
from locust import HttpUser, task, between

class QuickstartUser(HttpUser):
    wait_time = between(1, 2.5)


    @task
    def req_getCombatPoints(self):
        data = {
            "admin": "root",
            "description": "this is award",
            "count": "3",
            "begintime": "2021-07-28 19:48:03",
            "endtime": "2021-08-28 19:48:03",
            "content": "array('gold'=>'67','Diamond'=>'645')",
            "type": "2",
            "role": "1"
        }
        login_response = self.client.post('/GiftCode/creatgiftCode', data=data)

    @task
    def req_getLegalCvcAndUnlockedSoldier(self):
            data = {
               "admin": "root",
               "role": "1",
               "code": "code_OPCMgDUP"
            }
            login_response = self.client.post('/GiftCode/useCode', data=data)


    @task
    def req_getUnlockedSoldierJson(self):
            data = {
                "code":"code_OPCMgDUP"
            }
            login_response = self.client.post('/GiftCode/getCodeInfo', data=data)



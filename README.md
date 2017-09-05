# Two factor authentication demo website
### Description
This repo was intended to be my senior capstone project. I did some initial work on it, but as fate would have it the team decided on [Cry](https://github.com/vuphan314/cry) as our project.

The original idea was to have a lightweight, universal two factor authentication method. Specifically, my idea was exactly what Google implemented with their [two factor auhtentication method](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2) that allows a user to select yes or no. Unfortunately, I believe the only service that supports this is Google themselves. Every other service uses TOTP authentication, requiring more than just a simple yes or no press.

In summary, I believe that as developers find ways to promote security without burdening the user, the web will be a much more secure place. The Two Step project was intended to do exactly that.
### How to use
This project is incomplete, and therefore everything hasn't been ironed out. By following these steps, you should get a general idea of what I'm on about with the project though.
1. Navigate to https://twostep.electr0sheep.com on your Android device that has a fingerprint sensor (iOS is not supported)
2. Click on Download Android App
3. Install the APK
4. From https://twostep.electr0sheep.com, click on Create Account and create an account. DO NOT USE CREDENTIALS YOU USE ANYWHERE ELSE. I have taken some effort to make the site secure, but you should just enter some bogus username/password all the same.
5. Once the account has been created, open the Android App and Log in.
6. Going back to https://twostep.electr0sheep.com, login.
7. When you see the Waiting for authentication screen, you should have a notification on your phone, which you can click. After clicking, just follow the prompts.
8. If all went well, your browser will automatically redirect to a page that says Welcome, [username]!
